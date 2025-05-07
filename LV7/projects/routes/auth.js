const express = require('express');
const router = express.Router();
const User = require('../models/user');
const passport = require('passport');

// Registration form
router.get('/register', (req, res) => res.render('auth/register'));

// Register user
router.post('/register', async (req, res) => {
  try {
    const user = new User({ username: req.body.username, password: req.body.password });
    await user.save();
    res.redirect('/login');
  } catch (err) {
    res.render('auth/register', { error: 'Username already exists.' });
  }
});

// Login form
router.get('/login', (req, res) => res.render('auth/login'));

// Login user
router.post('/login', passport.authenticate('local', {
  successRedirect: '/projects',
  failureRedirect: '/login',
  failureFlash: true
}));

// Logout
router.get('/logout', (req, res) => {
  req.logout(() => {
    res.redirect('/login');
  });
});

module.exports = router;
