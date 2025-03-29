import React from 'react';
import ReactDOM from 'react-dom';
import App from './components/App';
import '../css/app.css';

// ReactDOM.render(<ExampleComponent />, document.getElementById('react-app'));

ReactDOM.createRoot(document.getElementById('react-app')).render(
    <React.StrictMode>
      <App/>
    </React.StrictMode>,
  )
