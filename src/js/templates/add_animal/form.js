const photo = document.querySelector('#form-wrapper form input[name="photo"]')
const form = document.querySelector('#form-wrapper form')
const feedbackLabel = document.querySelector('#feedback')

form.onsubmit = async (e) => {
  e.preventDefault()

  let formData = new FormData(form)

  const age = formData.get('age')
  const ageType = formData.get('age_type')

  formData.delete('age')
  formData.delete('age_type')

  formData.append('age', `${age} ${ageType}`)

  let res = await fetch('api/animal/', {
		method: 'post',
		body: formData,
  })

  if (!res.ok) {
    const { error } = await res.json()
    feedbackLabel.classList = 'form-error'
    feedbackLabel.innerHTML = error
    return
  }

  // TODO add link to animal page in the HTML
  feedbackLabel.classList = 'form-success'
  feedbackLabel.innerHTML = 'Animal added successfully'
  location.href = 'profile.php'
}
