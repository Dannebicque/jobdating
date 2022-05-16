/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)

import './styles/app.scss'
import 'bootstrap-icons/font/bootstrap-icons.css'

// start the Stimulus application
import './bootstrap'

window.bootstrap = require('bootstrap/dist/js/bootstrap.bundle.min')

import './js/vendor/OverlayScrollbars.min'
import './js/vendor/clamp.min'

import './js/base/init'
import './js/common'
import './js/scripts'
import './js/base/loader'
import {post} from './js/fetch'

var toastElList = [].slice.call(document.querySelectorAll('.toast'))
var toastList = toastElList.map(function (toastEl) {
  return new bootstrap.Toast(toastEl)
})

var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl, {placement: 'bottom'})
})


// Without jQuery
// Define a convenience method and use it
const ready = (callback) => {
  if (document.readyState != 'loading') callback()
  else document.addEventListener('DOMContentLoaded', callback)
}

ready(() => {
  /* Do things after DOM has fully loaded */
  toastList.forEach((toast) => {
    toast.show()
  })

  document.querySelectorAll('.sauvegardeCreneau').forEach((elem) => {
    elem.addEventListener('click', sauvegardeCreneau)
  })

  function sauvegardeCreneau (e) {
    console.log('sauvegardeCreneau')
    e.preventDefault()
    let creneau = e.target.dataset.creneau
    let url = e.target.dataset.url

    post(url, {creneau: creneau}).then(function (response) {
      console.log(response.route)

        toastList.forEach((toast) => {
          toast.show()
        })
      window.location.href = response.route

    })
  }

})



