const addForm = document.querySelector('#add-post-main form')
if (addForm) addForm.onsubmit = addPost

async function addPost(event) {
	event.preventDefault()

	const formData = new FormData(addForm)

	const res = await fetch('api/post/', {
		method: 'POST',
		body: formData,
	})

	const callContent = await res.json()

	if (res.status >= 400) alert(callContent.error)
	else location.reload()
}
