require('./../css/tailwind.scss');


import {execDocReady, docReady} from "./register";
import './init/rx_refresh';


docReady(() => {
   console.log('views work ready.');
});

execDocReady();