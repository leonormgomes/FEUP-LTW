// prevents accidental enters to submit the form
document.querySelectorAll('input').forEach((input) => {
	input.onkeypress = function (e) {
		if (e.key == 'Enter') e.preventDefault()
	}
})

updateImageEvent('#cover', '#cover_input')
updateImageEvent('#profile_photo > img', '#profile_photo_input')
