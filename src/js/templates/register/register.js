const registerForm = document.querySelector('form')
registerForm.onsubmit = register

async function register(event) {
	event.preventDefault()

	const firstName = document.querySelector('input[name=first_name]').value
	const lastName = document.querySelector('input[name=last_name]').value
	const username = document.querySelector('input[name=username]').value
	const email = document.querySelector('input[name=email]').value
	const password = document.querySelector('input[name=password]').value
	const confirmPassword = document.querySelector('input[name=confirm_password]').value
	const csrf = document.querySelector('input[name=csrf]').value

	// validates the inputs
	if (!validateRegister(firstName, lastName, username, password, confirmPassword)) return

	const res = await fetch('api/user/', {
		method: 'POST',
		headers: {
			Accept: 'application/json',
			'Content-Type': 'application/json',
		},
		body: JSON.stringify({
			first_name: firstName,
			last_name: lastName,
			username,
			email,
			password,
			csrf,
		}),
	})

	const content = await res.json()

	if (res.status >= 400) document.querySelector('#error_message').innerHTML = content.error
	else window.location.href = 'login.php'
}

function validateRegister(firstName, lastName, username, password, confirmPassword) {
	if (!regexName.test(firstName)) {
		document.querySelector('#error_message').innerHTML = 'First name can only have letters and spaces'
		return false
	}

	if (!regexName.test(lastName)) {
		document.querySelector('#error_message').innerHTML = 'Last name can only have letters and spaces'
		return false
	}

	if (!regexUsername.test(username)) {
		document.querySelector('#error_message').innerHTML = 'Username can only have letters, numbers, and underscores'
		return false
	}

	if (!regexPassword.test(password)) {
		document.querySelector('#error_message').innerHTML = 'Password needs at least 8 characters'
		return false
	}

	if (password !== confirmPassword) {
		document.querySelector('#error_message').innerHTML = "Passwords don't match"
		return false
	}

	return true
}
