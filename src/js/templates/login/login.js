const form = document.querySelector('form')
form.onsubmit = login

async function login(event) {
	event.preventDefault()

	const email = document.querySelector('input[name=email]').value
	const password = document.querySelector('input[name=password]').value
	const csrf = document.querySelector('input[name=csrf]').value

	const res = await fetch('api/auth/login.php', {
		method: 'POST',
		headers: {
			Accept: 'application/json',
			'Content-Type': 'application/json',
		},
		body: JSON.stringify({ email, password, csrf }),
	})

	const content = await res.json()

	if (res.status >= 400) document.querySelector('#error_message').innerHTML = content.error
	else window.location.href = 'feed.php'
}
