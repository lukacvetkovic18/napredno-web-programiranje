var mongoose = require('mongoose');

const projectSchema = new mongoose.Schema({
  name: { type: String, required: true },
  description: { type: String },
  price: { type: Number },
  leader: { type: mongoose.Schema.Types.ObjectId, ref: 'User' },
  tasks: { type: [String] },
  startDate: { type: Date },
  endDate: { type: Date },
  teamMembers: [{
    user: { type: mongoose.Schema.Types.ObjectId, ref: 'User' },
    role: { type: String }
  }],
  archived: { type: Boolean, default: false }
});

module.exports = mongoose.model('Project', projectSchema);