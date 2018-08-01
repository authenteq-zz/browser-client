# Authenteq Browser Library

This is a client-side library for [Authenteq](https://authenteq.com) service. You can see built libraries for CommonJS, ESM and UMD in `build/` directory.

## Usage

### Browser

Download `https://github.com/authenteq/client-lib/blob/master/dist/client-lib.umd.js` and include it in your HTML file.

For full code, refer to [samples](./samples/index.html) directory. Make sure to change `PARTNER ID` in the code.

Code preview:

```
<script src='./build/client-lib.umd.js'></script>
<script>
  var partnerId = '<<YOUR PARTNER ID>>';
  var scope = 'givenname,surname,dob,nationality,passportno,aml,kyc,address';

  Authenteq.connect(partnerId, scope, handleOnConnect, handleOnUserAuthenticate)

  function handleOnConnect(data) {
    // Show AQR code stored in 'svg'. You can convert it to display as <img> source, like this:
    // img.src = Authenteq.createAQRSvg(data.svg);
  }

  function handleOnUserAuthenticate(tokenId) {
    // Use tokenId to query Authenteq API. Make sure to access our API
    // from your backend, so you don't expose your `API key` in frontend.
  }
</script>
```

## How To Build

```bash
git clone https://github.com/authenteq/browser-client
cd client-lib
npm install
```

`npm run build` builds the library to `dist`, generating three files:

* `dist/client-lib.cjs.js`
    A CommonJS bundle, suitable for use in Node.js, that `require`s the external dependency. This corresponds to the `"main"` field in package.json
* `dist/client-lib.esm.js`
    an ES module bundle, suitable for use in other people's libraries and applications, that `import`s the external dependency. This corresponds to the `"module"` field in package.json
* `dist/client-lib.umd.js`
    a UMD build, suitable for use in any environment (including the browser, as a `<script>` tag), that includes the external dependency. This corresponds to the `"browser"` field in package.json

`npm run dev` builds the library, then keeps rebuilding it whenever the source files change using [rollup-watch](https://github.com/rollup/rollup-watch).

`npm test` builds the library, then tests it.

## License

[MIT](LICENSE).
