import slim from 'observable-slim'

function path (paths, obj) {
  paths = Array.isArray(paths) ? paths : paths.split('.')
  var val = obj
  var idx = 0
  while (idx < paths.length) {
    if (val == null) {
      return
    }
    val = val[paths[idx]]
    idx += 1
  }
  return val
}

class MyElement extends HTMLElement {
  constructor () {
    super(...arguments)

    this._listeners = []
    this._mapper = {}

    if (!this.shadowRoot) {
      // Attach shadow DOM
      this.attachShadow({mode: 'open'})
      // Append template content to custom element
      this.shadowRoot.appendChild(this.template.content.cloneNode(true))
    }
  }

  connectedCallback () {
    this.applyListeners()
    this.activateState()
  }

  activateState () {
    const instance = this

    const initial = (
      instance.data instanceof Function
      ? instance.data
      : () => (instance.data || {})
    )()

    this.shadowRoot.querySelectorAll('*')
      .map(node => {
        // Attribute sync
        Array.from(node.attributes)
          // e.g. :value
          .filter(attr => attr.name.startsWith(':'))
          .map(attr => {
            const name = attr.name.slice(1)
            node.removeAttribute(attr.name)
            if (name === 'children') {
              node.innerText = path(attr.value, initial)
            } else node.setAttribute(name, path(attr.value, initial))
            if (this._mapper.hasOwnProperty(attr.value)) {
              this._mapper[attr.value].push({name, node})
            } else this._mapper[attr.value] = [{name, node}]
          })
      })

    this.data = slim.create(
      initial,
      true,
      changes => {
        changes.map(change => {
          console.log(change.currentPath, instance._mapper)
          Object.keys(instance._mapper)
            .filter(paths => paths.startsWith(change.currentPath))
            .map(paths =>
              instance._mapper[paths].map(
                ({name, node}) => {
                  const newNode = path(paths, instance.data)
                  if (name === 'children') {
                    node.innerText = newNode
                  } else node.setAttribute(name, newNode)
                }
              )
            )
      })
      }
    )
  }

  diactivateState () {
    slim.remove(this.data)
  }

  disconnectedCallback () {
    this.clearListeners()
    this.diactivateState()
  }

  applyListeners () {
    const instance = this

    this.shadowRoot.querySelectorAll('*')
      .map(node =>
        Array.from(node.attributes)
          // e.g. @click
          .filter(attr => attr.name.startsWith('@'))
          .map(attr => {
            node.removeAttribute(attr.name)
            if (instance[attr.value] instanceof Function) {
              const handler = instance[attr.value].bind(instance)
              node.addEventListener(attr.name.slice(1), handler)
              instance._listeners.push({el: node, event: attr.name.slice(1), handler})
            }
          })
      )
  }

  clearListeners () {
    this._listeners.map(({el, event, handler}) => el.removeEventListener(event, handler))
    this._listeners.length = 0
  }
}

window.MyElement = MyElement

// Define custom element
window.exportTag = (name = '', Class = class extends MyElement {}) => {
  Class.template = Class.prototype.template = document.currentScript.parentElement.querySelector('template')
  return window.customElements.define(name, Class)
}

export default MyElement
