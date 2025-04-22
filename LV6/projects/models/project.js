var mongoose = require('mongoose');

const projectSchema = new mongoose.Schema({
  name: { type: String, required: true },
  description: { type: String },
  price: { type: Number },
  tasks: { type: [String] },
  startDate: { type: Date },
  endDate: { type: Date },
  teamMembers: [{ name: String, role: String }]
});

module.exports = mongoose.model('Project', projectSchema);