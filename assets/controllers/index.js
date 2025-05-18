import { Application } from '@hotwired/stimulus'
import ShowtimeFormController from './showtime-form-controller'
import RateSelectionController from "./rate-selection-controller"
application.register("rate-selection", RateSelectionController)


const application = Application.start()
application.register('showtime-form', ShowtimeFormController)
