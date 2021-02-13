require('./../css/tailwind.scss');

import {execDocReady, docReady} from "./register";

docReady(() => {
   console.log('views work ready');
});

execDocReady();