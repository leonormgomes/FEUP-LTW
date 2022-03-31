const deleteForm = document.querySelector('.delete-post-form')
if (deleteForm) deleteForm.onsubmit = deletePost

async function deletePost(event) {
  event.preventDefault()

  const postID = document.querySelector('input[name=postID]').value
  const csrf = document.querySelector('input[name=csrf]').value

  const res = await fetch('api/post/', {
    method: 'DELETE',
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ postID, csrf })
  })

  const callContent = await res.json()

  if (res.status >= 400) alert(callContent.error)
  else location.reload()
}
