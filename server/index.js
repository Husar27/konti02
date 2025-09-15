const express = require('express');
const bodyParser = require('body-parser');
const bcrypt = require('bcrypt');
const fs = require('fs-extra');
const cors = require('cors');

const app = express();
const PORT = 3000;
const USERS_FILE = './users.json';

app.use(cors());
app.use(bodyParser.json());

// Pomocnicza funkcja do odczytu użytkowników
async function readUsers() {
  try {
    const data = await fs.readFile(USERS_FILE, 'utf8');
    return JSON.parse(data);
  } catch (err) {
    return [];
  }
}

// Pomocnicza funkcja do zapisu użytkowników
async function writeUsers(users) {
  await fs.writeFile(USERS_FILE, JSON.stringify(users, null, 2));
}

// Rejestracja
app.post('/register', async (req, res) => {
  const { nick, password } = req.body;
  if (!nick || !password) {
    return res.status(400).json({ error: 'Nick i hasło są wymagane.' });
  }
  const users = await readUsers();
  if (users.find(u => u.nick === nick)) {
    return res.status(409).json({ error: 'Użytkownik już istnieje.' });
  }
  const hashedPassword = await bcrypt.hash(password, 10);
  users.push({ nick, password: hashedPassword });
  await writeUsers(users);
  res.status(201).json({ message: 'Użytkownik zarejestrowany.' });
});

// Logowanie
app.post('/login', async (req, res) => {
  const { nick, password } = req.body;
  if (!nick || !password) {
    return res.status(400).json({ error: 'Nick i hasło są wymagane.' });
  }
  const users = await readUsers();
  const user = users.find(u => u.nick === nick);
  if (!user) {
    return res.status(401).json({ error: 'Nieprawidłowy nick lub hasło.' });
  }
  const match = await bcrypt.compare(password, user.password);
  if (!match) {
    return res.status(401).json({ error: 'Nieprawidłowy nick lub hasło.' });
  }
  res.json({ message: 'Zalogowano pomyślnie.' });
});

app.listen(PORT, () => {
  console.log(`Serwer działa na http://localhost:${PORT}`);
});
