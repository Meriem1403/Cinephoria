import { Application } from '@hotwired/stimulus'
import ShowtimeFormController from './showtime-form-controller'

const application = Application.start()
application.register('showtime-form', ShowtimeFormController)
