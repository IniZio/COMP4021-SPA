# :mortar_board: UST.Course

> COMP4021 SPA Project

### API Documentaion: https://ustcourse.docs.apiary.io

## Development

```sh
npm i # or yarn

npm run dev # Runs dev server with live-reload

npm run build # Builds project for production

npm run serve # Serve the built project
```
#### Windows users:

```sh
# Serve the built project
"X:\path-to-php-folder\php.exe" -d extension_dir="X:\path-to-php-folder\ext" -d extension=sqlite3 -S localhost:8080 -t dist
```

## How to (Frontend)

#### 1. Add a new html file

1. Create a html file in `components/` folder, with following content:

   ```html
   <style>
   /* CSS here */
   </style>

   <template>
   <!-- HTML here -->
   </template>

   <script>
   // JS here
   exportTag('abc-xyz')
   </script>
   ```

2. Add the html import and its tag in `index.html`:

   ```html
   <body>
     <!-- After vendor imports -->
     <link rel="import" href="./components/abc-xyz.html">
     
     <abc-xyz></abc-xyz>
   </body>
   ```

### 2. Access children of the html file in its class

```js
exportTag('xyz-abc', class extends MyElement {
  someFunction () {
    console.log($(this.shadowRoot).children('#send-email'))
  }
}
```

### 3. Add event listeners to template content

```html
<template>
  <button @click="sendEmail">Send</button>
</template>

<script>
  exportTag('xyz-abc', class extends MyElement {
    sendEmail (e) {
      e.preventDefault()
      api.sendMail()
      console.log('sending email')
    }
  })
</script>
```
### 4. Use reactive state

```html
<template>
  <input :value="message" @input="changeMessage">
  <div :children="message"></div>
</template>

<script>
  exportTag('abc-xyz', class extends MyElement {
    // NOTE: Use a function that returns the initial value
    data () {
      return {
        cc: 100,
        message: 'qq'
      }
    }
    changeMessage (e) {
      this.data.message = e.target.value
    }
  })
</script>
```

### 5. Use global context

```html
<template>
  <input ~value="magic" @input="changeMessage">
  <div ~children="magic"></div>
</template>

<script>
  exportTag('abc-xyz', class extends MyElement {
    changeMessage (e) {
      this.context.magic = e.target.value
    }
  })
</script>
```

### 6. Use `x-for` directive

```html
<template>
  <div x-for="abc:messages" :children="abc"></div>
</template>

<script>
  exportTag('abc-xyz', class extends MyElement {
    data () {
      return {
        messages: ['hello', 'bye', 'magic']
      }
    }
  })
</script>
```

### 7. Use `x-if` and `x-else` directive

```html
<template>
  <button @click="toggleEdit">Edit</button>
  <input x-if="isEditting" :value="content" @input="changeContent">
  <div x-else :children="content"></div>
</template>

<script>
  exportTag('abc-xyz', class extends MyElement {
    data () {
      return {
        isEditting: false,
        content: ''
      }
    }
  
    toggleEdit () {
      this.data.isEditting = !this.data.isEditting
    }
  
    changeContent (e) {
      this.data.content = e.target.value
    }
  })
</script>
```

## How to (backend)

### 1. Set up sqlite database

```sh
cd server
sqlite3 data.db < api/init.sql
```
