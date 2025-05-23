// assets/controllers/showtime-form_controller.js
import { Controller } from '@hotwired/stimulus'
import "core-js/modules/web.url-search-params.js";

export default class extends Controller {
    static targets = [
        'movieSelect', 'language', 'roomSelect',
        'capacity', 'pmr', 'startTime', 'endTime'
    ]

    // Chargement des langues + durée du film au changement du film
    async render() {
        const movieId = this.movieSelectTarget?.value
        if (!movieId) return

        try {
            const response = await fetch(`/admin/api/movie/${movieId}/languages`)
            if (!response.ok) throw new Error('Failed to fetch movie languages')

            const result = await response.json()
            const languages = result.languages || []
            const duration = result.duration || 0

            // Réinitialisation des langues
            this.languageTarget.innerHTML = ''
            const placeholder = document.createElement('option')
            placeholder.value = ''
            placeholder.textContent = '-- Select a language --'
            placeholder.disabled = true
            placeholder.selected = true
            this.languageTarget.appendChild(placeholder)

            // Remplissage dynamique
            languages.forEach(lang => {
                const option = document.createElement('option')
                option.value = lang
                option.textContent = lang
                this.languageTarget.appendChild(option)
            })

            this.updateEndTime(duration)
        } catch (error) {
            console.error('Error fetching languages:', error)
        }
    }

    // Récupère la capacité et le nombre de PMR d'une salle
    async loadRoomData() {
        const roomId = this.roomSelectTarget?.value
        if (!roomId) return

        try {
            const response = await fetch(`/admin/api/room/${roomId}/capacity`)
            if (!response.ok) throw new Error('Failed to load room data')
            const data = await response.json()

            if (this.capacityTarget) this.capacityTarget.value = data.capacity
            if (this.pmrTarget) this.pmrTarget.value = data.pmr
        } catch (error) {
            console.error('Room data error:', error)
        }
    }

    // Calcule automatiquement l'heure de fin en fonction du début + durée
    updateEndTime(duration) {
        const startInput = this.startTimeTarget
        const endInput = this.endTimeTarget
        if (!startInput.value || !duration) return

        const [hours, minutes] = startInput.value.split(':').map(Number)
        const startDate = new Date()
        startDate.setHours(hours, minutes)

        const endDate = new Date(startDate.getTime() + duration * 60000)
        const hh = endDate.getHours().toString().padStart(2, '0')
        const mm = endDate.getMinutes().toString().padStart(2, '0')

        endInput.value = `${hh}:${mm}`
    }

    // À chaque changement d'heure de début, recalcule l'heure de fin et valide le créneau
    handleStartTimeChange() {
        const movieId = this.movieSelectTarget?.value
        if (!movieId) return

        fetch(`/admin/api/movie/${movieId}/languages`)
            .then(response => response.json())
            .then(data => {
                const duration = data.duration || 0
                this.updateEndTime(duration)
                this.validateTimeslot()
            })
            .catch(err => console.error('Movie fetch error:', err))
    }

    // Vérifie qu'il n'y a pas de conflit horaire dans la salle
    async validateTimeslot() {
        const roomId = this.roomSelectTarget?.value
        const date = this.element.querySelector('[name$="[date]"]').value
        const start = this.startTimeTarget.value
        const end = this.endTimeTarget.value
        const submitBtn = this.element.querySelector('button[type="submit"]')

        if (!roomId || !date || !start || !end) return

        try {
            const params = new URLSearchParams({ roomId, date, start, end })
            const response = await fetch(`/admin/api/showtime/check-conflict?${params.toString()}`)
            if (!response.ok) throw new Error('Conflict check failed')

            const data = await response.json()

            if (data.conflict) {
                alert('A showtime already exists in this room at that time. Please choose another slot.')
                this.startTimeTarget.value = ''
                this.endTimeTarget.value = ''
                if (submitBtn) {
                    submitBtn.disabled = true
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed')
                }
            } else {
                if (submitBtn) {
                    submitBtn.disabled = false
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed')
                }
            }
        } catch (err) {
            console.warn('Error validating timeslot:', err)
        }
    }
}
