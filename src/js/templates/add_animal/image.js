let URL = window.URL || window.webkitURL

const photoUpload = document.querySelector(
  '#form-wrapper form input[name="photo"]'
)

const photoDiv = document.querySelector('#form-wrapper #animal-photo label')

photoUpload.onchange = (e) => {
  let img = document.createElement('img')
  img.src = URL.createObjectURL(e.target.files[0])

  photoDiv.removeChild(photoDiv.firstChild)
  photoDiv.insertBefore(img, photoDiv.firstChild)
}
