class MyElement extends HTMLElement {
  constructor () {
    super(...arguments)

    this._listeners = []

    if (!this.shadowRoot) {
      // Attach shadow DOM
      this.attachShadow({mode: 'open'})
      // Append template content to custom element
      this.shadowRoot.appendChild(this.template.content.cloneNode(true))
    }
  }

  connectedCallback () {
    this.applyListeners()
  }

  disconnectedCallback () {
    this.clearListeners()
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
