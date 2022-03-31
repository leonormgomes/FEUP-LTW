/*
 * Sections
 */
const SectionsEnum = { POSTS: 0, PETS: 1, FAVORITES: 2, PROPOSALS: 3 }

const defaultSection = SectionsEnum.POSTS
let currentSection = defaultSection

const availableSectionsItems = document.querySelectorAll(
  '#profile-feed header ul li'
)

const availableSections = document.querySelectorAll('#profile-feed>main>div')

function getChildIndex(node) {
  let index = 0

  const { parentNode: parent } = node

  for (let item of parent.children)
    if (item === node) return index
    else ++index

  return -1
}

function clickHandler(event) {
  let target = event.target

  if (event.target.tagName !== 'LI') target = event.target.parentNode

  setActive(getChildIndex(target))
}

function setActive(sectionEnum) {
  availableSectionsItems.forEach((li, index) => {
    if (index !== sectionEnum) li.classList.remove('profile-section-selected')
    else if (!li.classList.contains('profile-section-selected'))
      li.classList.add('profile-section-selected')
  })

  availableSections.forEach((div, index) => {
    if (index !== sectionEnum) div.style.display = 'none'
    else div.style.display = 'flex'
  })

  currentSection = sectionEnum
}

setActive(currentSection)

availableSectionsItems.forEach((item) => (item.onclick = clickHandler))
