// regex validations
const regexName = /^[A-Za-zÀ-ÖØ-öø-ÿ ]{1,64}$/
const regexUsername = /^\w{1,64}$/
const regexPassword = /^.{8,}$/

// adds an event to update an image given by the imgQuery using an input type="file" given in the fileInputQuery
function updateImageEvent(imgQuery, fileInputQuery) {
	const input = document.querySelector(fileInputQuery)

	input.onchange = () => {
		if (input.files && input.files[0]) {
			const file = input.files[0]
			const reader = new FileReader()

			reader.onload = () => {
				const img = document.querySelector(imgQuery)
				img.src = reader.result
			}

			reader.readAsDataURL(file)
		}
	}
}
