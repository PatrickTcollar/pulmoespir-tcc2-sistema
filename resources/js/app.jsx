import './bootstrap'; // Importa suas depend\u00eancias como Axios
import '../css/app.css'; // Importa seu CSS principal (incluindo Tailwind)

// REMOVIDO: A linha 'import jsPDF from 'jspdf';' foi removida.
// jsPDF \u00e9 carregado via CDN no chat.blade.php e acess\u00edvel globalmente via window.jspdf.

import React from 'react';
import ReactDOM from 'react-dom/client';
import App from './components/App'; // AQUI: Importa o componente App do arquivo App.jsx

// Inicializa a aplica\u00e7\u00e3o React no elemento HTML com id 'exam-chat-root'
// Garante que o elemento existe antes de tentar renderizar
if (document.getElementById('exam-chat-root')) {
    ReactDOM.createRoot(document.getElementById('exam-chat-root')).render(
        <React.StrictMode>
            <App />
        </React.StrictMode>
    );
}
