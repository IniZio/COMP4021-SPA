class MyElement extends HTMLElement {
  constructor () {
    super(...arguments)
    // Attach shadow DOM
    this.attachShadow({mode: 'open'})
    // Append template content to custom element
    this.shadowRoot.appendChild(this.template.content.cloneNode(true))
  }
}

window.MyElement = MyElement

// Define custom element
window.exportTag = (name = '', Class = class extends MyElement {}) => {
  Class.template = Class.prototype.template = document.currentScript.parentElement.querySelector('template')
  return window.customElements.define(name, Class)
}

export default MyElement
