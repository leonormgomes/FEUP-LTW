const favoriteForm = document.querySelector('#favorite-form')
if (favoriteForm) favoriteForm.onsubmit = favorite

async function favorite(event) {
	event.preventDefault()

	const animalID = document.querySelector('#favorite-form input[name=animal_id]').value
	const csrf = document.querySelector('#favorite-form input[name=csrf]').value

	const res = await fetch('api/animal/favorite.php', {
		method: 'POST',
		headers: {
			Accept: 'application/json',
			'Content-Type': 'application/json',
		},
		body: JSON.stringify({ animalID, csrf }),
	})
	const content = await res.json()

	if (res.status >= 400) alert(content.error)
	else {
		removeFavForm()
		addUnfavoriteForm(csrf, animalID)
	}
}

const unfavoriteForm = document.querySelector('#unfavorite-form')
if (unfavoriteForm) unfavoriteForm.onsubmit = unfavorite

async function unfavorite(event) {
	event.preventDefault()

	const animalID = document.querySelector('#unfavorite-form input[name=animal_id]').value
	const csrf = document.querySelector('#unfavorite-form input[name=csrf]').value

	const res = await fetch('api/animal/unfavorite.php', {
		method: 'POST',
		headers: {
			Accept: 'application/json',
			'Content-Type': 'application/json',
		},
		body: JSON.stringify({ animalID, csrf }),
	})
	const content = await res.json()

	if (res.status >= 400) alert(content.error)
	else {
		removeFavForm()
		addFavoriteForm(csrf, animalID)
	}
}

function removeFavForm() {
	const form = document.querySelector('#fav-form')
	form.innerHTML = ''
}

function addFavoriteForm(csrf, animalID) {
	const form = document.createElement('form')
	form.id = 'favorite-form'
	form.onsubmit = favorite

	const animalIDInput = document.createElement('input')
	animalIDInput.type = 'hidden'
	animalIDInput.name = 'animal_id'
	animalIDInput.value = animalID

	const csrfInput = document.createElement('input')
	csrfInput.type = 'hidden'
	csrfInput.name = 'csrf'
	csrfInput.value = csrf

	const button = document.createElement('button')
	button.type = 'submit'
	button.id = 'profile-favorite'
	button.classList.add('clickable')
	button.innerHTML = '<i class="far fa-heart"></i>Favorite'

	form.appendChild(animalIDInput)
	form.appendChild(csrfInput)
	form.appendChild(button)
	document.querySelector('#fav-form').appendChild(form)

	console.log(document.querySelector('#fav-form'))
}

function addUnfavoriteForm(csrf, animalID) {
	const form = document.createElement('form')
	form.id = 'unfavorite-form'
	form.onsubmit = unfavorite

	const animalIDInput = document.createElement('input')
	animalIDInput.type = 'hidden'
	animalIDInput.name = 'animal_id'
	animalIDInput.value = animalID

	const csrfInput = document.createElement('input')
	csrfInput.type = 'hidden'
	csrfInput.name = 'csrf'
	csrfInput.value = csrf

	const button = document.createElement('button')
	button.type = 'submit'
	button.id = 'profile-favorite'
	button.classList.add('clickable')
	button.innerHTML = '<i class="fas fa-heart"></i>Favorite'

	form.appendChild(animalIDInput)
	form.appendChild(csrfInput)
	form.appendChild(button)
	document.querySelector('#fav-form').appendChild(form)
}

const questionForm = document.querySelector('#question-form')
if (questionForm) questionForm.onsubmit = question

async function question(event) {
	event.preventDefault()

	const animalID = document.querySelector('input[name=animalID]').value
	const content = document.querySelector('textarea[name=content]').value
	const csrf = document.querySelector('input[name=csrf]').value

	const res = await fetch('api/question/', {
		method: 'POST',
		headers: {
			Accept: 'application/json',
			'Content-Type': 'application/json',
		},
		body: JSON.stringify({ animalID, content, csrf }),
	})

	const callContent = await res.json()

	if (res.status >= 400) alert(callContent.error)
	else location.reload()
}

const responseToQuestionForm = document.querySelector('#responseToQuestionForm')
if (responseToQuestionForm) responseToQuestionForm.onsubmit = responseToQuestion

async function responseToQuestion(event) {
	event.preventDefault()

	const questionID = document.querySelector('input[name=questionID]').value
	const content = document.querySelector('textarea[name=content]').value

	const res = await fetch('api/responseToQuestion/', {
		method: 'POST',
		headers: {
			Accept: 'application/json',
			'Content-Type': 'application/json',
		},
		body: JSON.stringify({ questionID, content }),
	})

	const callContent = await res.json()

	if (res.status >= 400) alert(callContent.error)
	else location.reload()
}

const proposeAdoptionButton = document.querySelector('#profile-propose-adoption')
if (proposeAdoptionButton) proposeAdoptionButton.onclick = proposeAdoption

async function proposeAdoption(event) {
	event.preventDefault()

	const animalID = document.querySelector('input[name=animalID]').value
	const csrf = document.querySelector('input[name=csrf]').value

	const res = await fetch('api/adoption/request.php', {
		method: 'POST',
		headers: {
			Accept: 'application/json',
			'Content-Type': 'application/json',
		},
		body: JSON.stringify({ animal_id: animalID, csrf }),
	})

	const callContent = await res.json()

	if (res.status >= 400) alert(callContent.error)
	else location.reload()
}

const listAdoptionButton = document.querySelector('#profile-list-adoption')
if (listAdoptionButton) listAdoptionButton.onclick = listAdoption

async function listAdoption(event) {
	event.preventDefault()

	const animalID = document.querySelector('input[name=animalID]').value
	const csrf = document.querySelector('input[name=csrf]').value

	const res = await fetch('api/adoption/list.php', {
		method: 'POST',
		headers: {
			Accept: 'application/json',
			'Content-Type': 'application/json',
		},
		body: JSON.stringify({ animal_id: animalID, csrf }),
	})

	const callContent = await res.json()

	if (res.status >= 400) alert(callContent.error)
	else location.reload()
}

const accepts = document.querySelectorAll('.accept')
accepts.forEach((acceptButton) => {
	if (acceptButton) acceptButton.onclick = accept
})

async function accept(event) {
	event.preventDefault()
	acceptButton = event.target

	const email = acceptButton.dataset.email
	const animalID = document.querySelector('input[name=animalID]').value
	const csrf = document.querySelector('input[name=csrf]').value

	const res = await fetch('api/adoption/accept.php', {
		method: 'POST',
		headers: {
			Accept: 'application/json',
			'Content-Type': 'application/json',
		},
		body: JSON.stringify({ animal_id: animalID, csrf, person_email: email }),
	})

	const callContent = await res.json()

	if (res.status >= 400) alert(callContent.error)
	else location.reload()
}

const refuses = document.querySelectorAll('.refuse')
refuses.forEach((refuseButton) => {
	if (refuseButton) refuseButton.onclick = refuse
})

async function refuse(event) {
	event.preventDefault()
	refuseButton = event.target

	const email = refuseButton.dataset.email
	const animalID = document.querySelector('input[name=animalID]').value
	const csrf = document.querySelector('input[name=csrf]').value

	const res = await fetch('api/adoption/refuse.php', {
		method: 'POST',
		headers: {
			Accept: 'application/json',
			'Content-Type': 'application/json',
		},
		body: JSON.stringify({ animal_id: animalID, csrf, person_email: email }),
	})

	const callContent = await res.json()

	if (res.status >= 400) alert(callContent.error)
	else location.reload()
}
