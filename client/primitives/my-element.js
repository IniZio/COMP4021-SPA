import slim from 'observable-slim'

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

    this.shadowRoot.querySelectorAll('*')
      .map(node => {
        // Attribute sync
        Array.from(node.attributes)
          // e.g. :value
          .filter(attr => attr.name.startsWith(':'))
          .map(attr => {
            const name = attr.name.slice(1)
            node.removeAttribute(attr.name)
            if (this._mapper.hasOwnProperty(attr.value)) {
              this._mapper[attr.value].push({name, node})
            } else this._mapper[attr.value] = [{name, node}]
          })
      })

    this.data = slim.create(
      instance.data instanceof Function
      ? instance.data()
      : instance.hasOwnProperty('data')
        ? instance.data
        : {},
      true,
      changes => {
        changes.map(change => 
          instance._mapper[change.currentPath].map(
            ({name, node}) => {
              if (name === 'children') {
                node.innerText = instance.data[change.currentPath]
              } else node.setAttribute(name, instance.data[change.currentPath])
            }
          )
        )
      // console.log(JSON.stringify(changes, null, 2))
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
