var navbar = document.getElementById('navbar')

window.onscroll = () => {
  if (window.scrollY <= 100) navbar.style.backgroundColor = 'rgba(0, 0, 0, 0)'

  if (window.scrollY > 100 && window.scrollY <= 600)
    navbar.style.backgroundColor = `rgba(255, 253, 251, ${
      (window.scrollY - 100) / 500
    })`
}

const logoutButton = document.querySelector('a#logout')
logoutButton.onclick = logout

async function logout(event) {
	event.preventDefault()

	const res = await fetch('api/auth/logout.php', {
		method: 'POST',
	})

	const content = await res.json()

	if (res.status >= 400) alert(content.error)
	else window.location.href = 'index.php'
}
