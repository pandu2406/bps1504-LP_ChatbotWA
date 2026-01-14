const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');
const axios = require('axios');

// Configuration
const LARAVEL_WEBHOOK_URL = 'http://localhost:8003/api/whatsapp/webhook';

console.log('🚀 Starting Local WhatsApp Gateway...');

const client = new Client({
    authStrategy: new LocalAuth(),
    puppeteer: {
        headless: true,
        args: ['--no-sandbox']
    }
});

client.on('qr', (qr) => {
    console.log('⚡ Scan this QR Code with your WhatsApp:');
    qrcode.generate(qr, { small: true });
});

client.on('ready', () => {
    console.log('✅ WhatsApp Client is Ready!');
    console.log('Listening for messages...');
});

client.on('message', async msg => {
    // Avoid processing status updates or empty messages
    if (!msg.body) return;

    console.log(`📩 Incoming from ${msg.from}: ${msg.body}`);

    try {
        // Forward to Laravel (AI Logic)
        const response = await axios.post(LARAVEL_WEBHOOK_URL, {
            message: msg.body,
            sender: msg.from
        });

        const replyText = response.data.reply;

        if (replyText) {
            console.log(`🤖 AI Reply: ${replyText}`);
            // Send Reply back to user
            await msg.reply(replyText);
        }

    } catch (error) {
        console.error('❌ Error communicating with Laravel:', error.message);
    }
});

client.initialize();
