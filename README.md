# :mortar_board: UST.Course

> COMP4021 SPA Project

### API Documentaion: https://ustcourse.docs.apiary.io

## Development

```sh
npm i # or yarn

npm run dev # Runs dev server with live-reload

npm run build # Builds project for production
```

## How to

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
   importHtml('abc-xyz')
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

