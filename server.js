const express = require('express');
const path = require('path');
const http = require('http');
const socketIo = require('socket.io');
const cors = require('cors');
const mongoose = require('mongoose'); // MongoDB

// Créer une application Express
const app = express();
const server = http.createServer(app);

// Configurer CORS
app.use(cors({
    origin: "http://localhost:3000", // URL de ton frontend
    methods: ["GET", "POST"],
    credentials: true
}));

// Créer le serveur socket.io avec le serveur HTTP
const io = socketIo(server, {
    cors: {
        origin: "http://localhost:3000",  // L'URL de ton frontend
        methods: ["GET", "POST"],
        credentials: true
    }
});

// Connexion à MongoDB
mongoose.set('strictQuery', false);  // Pour éviter les avertissements
mongoose.connect('mongodb://localhost:27017/chatApp', {
    useNewUrlParser: true,
    useUnifiedTopology: true
}).then(() => {
    console.log('Connecté à MongoDB');
}).catch(err => {
    console.error('Erreur de connexion à MongoDB:', err);
});

// Définir un schéma pour les messages
const messageSchema = new mongoose.Schema({
    event_id: String,   // ID de l'événement
    username: String,   // Nom d'utilisateur
    message: String,    // Contenu du message
    timestamp: { type: Date, default: Date.now } // Horodatage
});

// Modèle pour les messages
const Message = mongoose.model('Message', messageSchema);

// Servir les fichiers statiques du dossier 'chat-temps-reel'
app.use(express.static(path.join(__dirname, 'chat-temps-reel')));

io.on('connection', (socket) => {
    console.log('Un utilisateur est connecté');

    // Rejoindre une salle spécifique à un événement
    socket.on('joinEvent', async (event_id) => {
        try {
            socket.join(event_id); // L'utilisateur rejoint la salle de l'événement
            console.log(`Utilisateur a rejoint l'événement ${event_id}`);

            // Charger les messages existants pour cet événement depuis MongoDB
            const messages = await Message.find({ event_id }).sort({ timestamp: 1 });
            socket.emit('loadMessages', messages); // Envoyer les messages existants au nouvel utilisateur
        } catch (err) {
            console.error('Erreur lors de la récupération des messages:', err);
        }
    });

    // Recevoir un message et l'envoyer à la salle de l'événement
    socket.on('message', async (data) => {
        const { event_id, username, message } = data;

        try {
            // Enregistrer le message dans MongoDB
            const newMessage = new Message({ event_id, username, message });
            await newMessage.save();

            // Diffuser le message dans la salle de l'événement
            io.to(event_id).emit('message', { username, message, timestamp: newMessage.timestamp });
        } catch (err) {
            console.error('Erreur lors de l\'enregistrement du message:', err);
        }
    });

    // Déconnexion
    socket.on('disconnect', () => {
        console.log('Un utilisateur a quitté');
    });
});

// Démarrer le serveur sur le port 
const PORT = process.env.PORT || 4002;
server.listen(PORT, () => {
    console.log(`Serveur lancé sur http://localhost:${PORT}`);
});
