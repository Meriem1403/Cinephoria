// assets/controllers/rate-selection-controller.js
import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static values = {
        priceStandard: Number,
        countSeats:    Number,
        pricesSpecial: Array
    }
    static targets = [ "standardCount", "specialCounts", "total" ]

    connect() {
        // Stimulus values
        this.priceStandard = this.priceStandardValue
        this.countSeats    = this.countSeatsValue
        this.pricesSpecial = this.pricesSpecialValue

        this.updateTotal()
    }

    // Standard tickets
    incrementStandard() { this.adjustStandard(+1) }
    decrementStandard() { this.adjustStandard(-1) }

    adjustStandard(delta) {
        let val = parseInt(this.standardCountTarget.value, 10) + delta
        val = Math.max(0, Math.min(this.countSeats, val))
        this.standardCountTarget.value = val
        this.updateTotal()
    }

    // Special tickets
    incrementSpecial(e) { this.adjustSpecial(e.currentTarget.dataset.index, +1) }
    decrementSpecial(e) { this.adjustSpecial(e.currentTarget.dataset.index, -1) }

    adjustSpecial(idx, delta) {
        const input = this.specialCountsTargets.find(el => el.dataset.index == idx)
        let val = parseInt(input.value, 10) + delta

        // ne pas dépasser le total de sièges
        if (delta > 0 && this.totalAssigned() >= this.countSeats) return

        val = Math.max(0, val)
        input.value = val
        this.updateTotal()
    }

    totalAssigned() {
        let sum = 0
        if (this.hasStandardCountTarget) {
            sum += parseInt(this.standardCountTarget.value, 10)
        }
        this.specialCountsTargets.forEach(el => {
            sum += parseInt(el.value, 10)
        })
        return sum
    }

    updateTotal() {
        let total = 0
        if (this.hasStandardCountTarget) {
            total += this.priceStandard * parseInt(this.standardCountTarget.value, 10)
        }
        this.specialCountsTargets.forEach((el, idx) => {
            total += this.pricesSpecial[idx].price * parseInt(el.value, 10)
        })
        this.totalTarget.textContent = total.toFixed(2) + " €"
    }
}
