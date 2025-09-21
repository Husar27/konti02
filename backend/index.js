import express from 'express';
import bcrypt from 'bcrypt';
import sqlite3 from 'sqlite3';
import { open } from 'sqlite';
import cors from 'cors';

const app = express();
const PORT = 3001;

app.use(cors());
app.use(express.json());

let db;

(async () => {
  db = await open({
    filename: './users.db',
    driver: sqlite3.Database
  });
  await db.run(`CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    login TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL
  )`);
})();

// Rejestracja
app.post('/register', async (req, res) => {
  const { login, password } = req.body;
  if (!login || !password) {
    return res.status(400).json({ error: 'Login i hasło są wymagane.' });
  }
  try {
    const hash = await bcrypt.hash(password, 10);
    await db.run('INSERT INTO users (login, password) VALUES (?, ?)', [login, hash]);
    res.status(201).json({ message: 'Użytkownik zarejestrowany.' });
  } catch (err) {
    if (err.code === 'SQLITE_CONSTRAINT') {
      res.status(409).json({ error: 'Login już istnieje.' });
    } else {
      res.status(500).json({ error: 'Błąd serwera.' });
    }
  }
});

// Logowanie
app.post('/login', async (req, res) => {
  const { login, password } = req.body;
  if (!login || !password) {
    return res.status(400).json({ error: 'Login i hasło są wymagane.' });
  }
  const user = await db.get('SELECT * FROM users WHERE login = ?', [login]);
  if (!user) {
    return res.status(401).json({ error: 'Nieprawidłowy login lub hasło.' });
  }
  const match = await bcrypt.compare(password, user.password);
  if (!match) {
    return res.status(401).json({ error: 'Nieprawidłowy login lub hasło.' });
  }
  res.json({ message: 'Zalogowano pomyślnie.' });
});

app.listen(PORT, () => {
  console.log(`Backend działa na http://localhost:${PORT}`);
});
