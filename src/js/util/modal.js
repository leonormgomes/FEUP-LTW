let modalEnabled = false

let startButton, modalWrapper, modal, closeButton

function makeModal(modalWrapperQuery, modalQuery, closeButtonQuery) {
  modalWrapper = document.querySelector(modalWrapperQuery)
  modal = document.querySelector(modalQuery)
  closeButton = document.querySelector(closeButtonQuery)

  enablePostModal()
  closeButton.onclick = disablePostModal
  modalWrapper.onclick = modalClickHandler
}

function enablePostModal() {
  document.body.style.overflow = 'hidden'
  modalWrapper.style.display = 'flex'

  modalEnabled = true
}

function disablePostModal() {
  document.body.style.overflow = 'initial'
  modalWrapper.style.display = 'none'

  modalEnabled = false
}

function modalClickHandler(event) {
  if (modalEnabled) {
    if (!modal.contains(event.target)) disablePostModal()
  }
}
