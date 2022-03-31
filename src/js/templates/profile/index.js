// adds the popup modals
document.querySelector('#profile-following').onclick = () =>
	makeModal('#following-modal-wrapper', '#show-following', '#show-following header button')
document.querySelector('#profile-followers').onclick = () =>
	makeModal('#followers-modal-wrapper', '#show-followers', '#show-followers header button')

const followForm = document.querySelector('#follow-form')
if (followForm) followForm.onsubmit = follow

async function follow(event) {
	event.preventDefault()

	const username = document.querySelector('input[name=username]').value
	const csrf = document.querySelector('#follow-form input[name=csrf]').value

	const res = await fetch('api/user/follow.php', {
		method: 'POST',
		headers: {
			Accept: 'application/json',
			'Content-Type': 'application/json',
		},
		body: JSON.stringify({ username, csrf }),
	})

	const content = await res.json()

	if (res.status >= 400) alert(content.error)
	else window.location.href = 'profile.php?username=' + username
}

const unfollowForm = document.querySelector('#unfollow-form')
if (unfollowForm) unfollowForm.onsubmit = unfollow

async function unfollow(event) {
	event.preventDefault()

	const username = document.querySelector('input[name=username]').value
	const csrf = document.querySelector('#unfollow-form input[name=csrf]').value

	const res = await fetch('api/user/unfollow.php', {
		method: 'POST',
		headers: {
			Accept: 'application/json',
			'Content-Type': 'application/json',
		},
		body: JSON.stringify({ username, csrf }),
	})

	const content = await res.json()

	if (res.status >= 400) alert(content.error)
	else window.location.href = 'profile.php?username=' + username
}

const postForm = document.querySelector('#add-post-form')
if (postForm) postForm.onsubmit = post

async function post(event) {
	event.preventDefault()

	let formData = new FormData(postForm)

	const res = await fetch('api/post/', {
		method: 'POST',
		body: formData,
	})

	const content = await res.json()

	if (res.status >= 400) alert(content.error)
	else window.location.href = location.reload()
}

async function getProposals() {
	const res = await fetch('api/adoption/', {
		method: 'GET',
	})

	const content = await res.json()

	if (res.status >= 400) alert(content.error)

	return content
}

const listedDiv = document.querySelector('#profile-listed')
const proposalsDiv = document.querySelector('#profile-proposals')

function buildListed(animal) {
	const div = document.createElement('div')
	div.className = 'search-result'

	div.innerHTML = `
        <div class="search-image">
        <img
        src="./database/images/${animal.photo}"
        alt="user-photo">
        </div>
        <div class="search-description">
        <header>
        <h4>${animal.name} (${animal.species}), ${animal.age}</h4>
        </header>
        <main>
        <span>${animal.listed_for_adoption === '0' ? 'Not listed' : 'Listed'} &bull; ${animal.location}</span>`

	if (animal.proposed) div.innerHTML += `<span class="notification">Has new proposes!</span>`

	div.innerHTML += `</main> </div>`
            
	div.onclick = () => (window.location.href = `./animal.php?id=${animal.id}`)
	return div
}

function buildProposal(animal) {
	const div = document.createElement('div')
	div.className = 'search-result'

	div.innerHTML = `
        <div class="search-image">
          <img
            src="./database/images/${animal.photo}"
            alt="user-photo">
        </div>
        <div class="search-description">
					<header>
						<h4>${animal.name} (${animal.species}), ${animal.age}</h4>
					</header>
					<main>
						<span>${animal.status.charAt(0).toUpperCase() + animal.status.toLowerCase().slice(1)} &bull; ${animal.location}</span>
					</main>
        </div>
  `
	div.onclick = () => (window.location.href = `./animal.php?id=${animal.id}`)
	return div
}

async function buildProposals() {
  const { listed, proposals } = await getProposals()

	if (listedDiv) {
		listedDiv.innerHTML = ''

		if (listed.length !== 0) {
			listed.forEach((animal) => listedDiv.appendChild(buildListed(animal)))
		} else {
			listedDiv.innerHTML = `There are no animals listed for adoption`
		}
	} 
	
	if (proposalsDiv) {
		proposalsDiv.innerHTML = ''

		if (proposals.length !== 0) {
			proposals.forEach((animal) => proposalsDiv.appendChild(buildProposal(animal)))
		} else {
			proposalsDiv.innerHTML = `You have no proposals`
		}
	}
}

buildProposals()
