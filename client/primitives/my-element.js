const camelCaseToDash = str =>
  str.replace( /([a-z])([A-Z])/g, '$1-$2' ).toLowerCase()

class MyElement extends HTMLElement {
  constructor () {
    super(...arguments)
    // Attach shadow DOM
    this.attachShadow({mode: 'open'})
    // Append template content to custom element
    // const [template] = document.currentScript && document.currentScript.ownerDocument.getElementsByTagName('template')
    const template = document.getElementById(camelCaseToDash(this.el || this.constructor.name))
    this.shadowRoot.appendChild(template.content.cloneNode(true))
  }
}

window.MyElement = MyElement

// Define custom element
window.importHtml = (name, Class) => {
  Class.el = Class.prototype.el = name
  return window.customElements.define(name, Class)
}

export default MyElement
