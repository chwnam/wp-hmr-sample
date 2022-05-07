import React from 'react';
import ReactDOM from 'react-dom';
import Dummy from "./dummy";

function App(props) {
    const {title, you} = props;

    return (
        <div>
            <p>Oh! Hello, new place?!</p>
            <p>You are {title}. {you}. Good evening.</p>
            <Dummy />
            ?
        </div>
    )
}

const wpHmrSample = Object.assign({
    you: 'Unknown',
    title: '',
}, window.hasOwnProperty('wpHmrSample') ? window.wpHmrSample : {})

ReactDOM.render(<App {...wpHmrSample} />, document.getElementById('wp-hmr-sample'))

if (module.hot) {
    module.hot.accept();
}
