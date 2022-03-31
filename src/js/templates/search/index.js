const animalsDiv = document.querySelector('#search-animals div')
const usersDiv = document.querySelector('#search-users div')

async function search(url) {
  const res = await fetch(`api/search/?${url}`, {
    method: 'GET',
    headers: {
      Accept: 'application/json'
    }
  })

  const content = await res.json()

  if (res.status >= 400) alert(content.error)

  console.log(content)

  return content
}

function buildAnimalDiv(animal) {
  const div = document.createElement('div')
  div.className = 'search-result'

  div.innerHTML = `
        <div class="search-image">
          <img
            src="./database/images/${animal.photo}"
            alt="animal-photo">
        </div>
        <div class="search-description">
          <header>
            <h4>${animal.name} (${animal.species}), ${animal.age}</h4>
          </header>
          <main>
            <span>${
              animal.listed_for_adoption === '0' ? 'Not listed' : 'Listed'
            } &bull; ${animal.location}</span>
          </main>
        </div>
  `

  div.onclick = () => (window.location.href = `./animal.php?id=${animal.id}`)
  return div
}

function buildUserDiv(user) {
  const div = document.createElement('div')
  div.className = 'search-result'

  div.innerHTML = `
        <div class="search-image">
          <img
            src="./database/images/${user.profile_picture}"
            alt="user-photo">
        </div>
        <div class="search-description">
          <header>
            <h4>${user.first_name} ${user.last_name}</h4>
          </header>
          <main>
            <span>@${user.username} &bull; ${user.location || 'No location'}</span>
          </main>
        </div>
  `

  div.onclick = () =>
    (window.location.href = `./profile.php?username=${user.username}`)
  return div
}

let content
let filteringCheckbox = document.querySelector('#listed-checkbox input')

async function build() {
  const url_query = window.location.search.substr(1)
  const search_parameter = url_query.split('=')[1]

  content = await search(url_query)

  const { animals, users } = content

  animalsDiv.innerHTML = ''
  usersDiv.innerHTML = ''

  if (animals.length !== 0) {
    animals.forEach((animal) => animalsDiv.appendChild(buildAnimalDiv(animal)))
  } else {
    animalsDiv.innerHTML = `Your search ${search_parameter} did not find any animal`
  }

  if (users.length !== 0) {
    users.forEach((user) => usersDiv.appendChild(buildUserDiv(user)))
  } else {
    usersDiv.innerHTML = `Your search ${search_parameter} did not find any user`
  }
}

let filtered

function filterAnimals() {
  let filtering = filteringCheckbox.checked

  const { animals } = content

  if (!filtered && content)
    filtered = animals.filter((animal) => animal.listed_for_adoption === '1')

  animalsDiv.innerHTML = ''

  if (filtering) {
    if (filtered.length !== 0) {
      filtered.forEach((animal) =>
        animalsDiv.appendChild(buildAnimalDiv(animal))
      )
    } else {
      animalsDiv.innerHTML = `There are no animals listed for adoption`
    }

    return
  }

  if (animals.length !== 0) {
    animals.forEach((animal) => animalsDiv.appendChild(buildAnimalDiv(animal)))
  } else {
    animalsDiv.innerHTML = `Your search ${search_parameter} did not find any animal`
  }
}

build()

filteringCheckbox.onchange = filterAnimals
