const commentForm = document.querySelector('#comment-form')
commentForm.onsubmit = comment

async function comment(event) {
	event.preventDefault()

	const postID = document.querySelector('input[name=postID]').value
	const content = document.querySelector('textarea[name=content]').value
	const csrf = document.querySelector('input[name=csrf]').value

	const res = await fetch('api/comment/', {
		method: 'POST',
		headers: {
			Accept: 'application/json',
			'Content-Type': 'application/json',
		},
		body: JSON.stringify({ postID, content, csrf }),
	})

	const callContent = await res.json()

	if (res.status >= 400) alert(callContent.error)
	else location.reload()
}
