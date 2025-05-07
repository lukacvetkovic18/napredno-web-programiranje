const express = require('express');
const router = express.Router();
const mongoose = require('mongoose');
const bodyParser = require('body-parser');
const methodOverride = require('method-override');
const Project = require('../models/project');
const User = require('../models/user');

function ensureAuthenticated(req, res, next) {
    if (req.isAuthenticated()) return next();
    res.redirect('/login');
}

// Middleware for parsing and overriding methods
router.use(bodyParser.urlencoded({ extended: true }));
router.use(methodOverride(function(req, res) {
    if (req.body && typeof req.body === 'object' && '_method' in req.body) {
        return req.body._method;
    }
}));

// Routes for Projects
router.route('/')
    // GET all projects
    .get(async function (req, res, next) {
        try {
            const projects = await Project.find({});
            res.format({
                html: function () {
                    res.render('projects/index', {
                        title: 'All Projects',
                        projects: projects
                    });
                },
                json: function () {
                    res.json(projects);
                }
            });
        } catch (err) {
            next(err);
        }
    })
    // POST a new project
    .post(ensureAuthenticated, async function (req, res) {
        try {
            const projectData = { ...req.body, leader: req.user._id };
            const project = new Project(projectData);
            const createdProject = await project.save();
            res.format({
                html: function () {
                    res.location("projects");
                    res.redirect("/projects");
                },
                json: function () {
                    res.json(createdProject);
                }
            });
        } catch (err) {
            res.status(400).send({ msg: 'Error creating project: ' + err });
        }
    });

/* GET New Project page. */
router.get('/new', ensureAuthenticated, function (req, res) {
    res.render('projects/new', { title: 'Add New Project' });
});

// IMPORTANT: Specific routes BEFORE param routes
router.get('/leader', ensureAuthenticated, async (req, res, next) => {
    try {
        const projects = await Project.find({ leader: req.user._id })
            .populate('teamMembers.user');
        res.render('projects/leader', { 
            title: 'My Leading Projects', 
            projects 
        });
    } catch (err) {
        next(err);
    }
});

router.get('/member', ensureAuthenticated, async (req, res, next) => {
    try {
        const projects = await Project.find({ 
            'teamMembers.user': req.user._id 
        }).populate('leader teamMembers.user');
        
        res.render('projects/member', { 
            title: 'My Member Projects', 
            projects 
        });
    } catch (err) {
        next(err);
    }
});

router.get('/archive', ensureAuthenticated, async (req, res, next) => {
    try {
        const projects = await Project.find({
            archived: true,
            $or: [
                { leader: req.user._id },
                { 'teamMembers.user': req.user._id }
            ]
        }).populate('leader teamMembers.user');

        res.render('projects/archive', {
            title: 'Project Archive',
            projects
        });
    } catch (err) {
        next(err);
    }
});

// Middleware to validate :id
router.param('id', async function (req, res, next, id) {
    try {
        // Validate ObjectId before query
        if (!mongoose.Types.ObjectId.isValid(id)) {
            const err = new Error('Invalid ID format');
            err.status = 400;
            return next(err);
        }
        
        const project = await Project.findById(id)
            .populate('teamMembers.user')
            .populate('leader');
            
        if (!project) {
            const err = new Error('Not Found');
            err.status = 404;
            return next(err);
        }
        req.project = project;
        next();
    } catch (err) {
        next(err);
    }
});

// All param routes AFTER specific routes
router.route('/:id')
    // GET a specific project by ID
    .get(async function(req, res, next) {
        try {
            const users = await User.find();
            res.format({
                html: function() {
                    res.render('projects/show', {
                        project: req.project,
                        users: users
                    });
                },
                json: function() {
                    res.json(req.project);
                }
            });
        } catch (err) {
            next(err);
        }
    })
    // PUT to update a specific project by ID
    .put(ensureAuthenticated, async function (req, res, next) {
        try {
            console.log("PUT request received for ID:", req.params.id);
            console.log("Request body:", req.body);
            
            // Check if user is the project leader
            if (req.project.leader._id.toString() !== req.user._id.toString()) {
                return res.status(403).send('Forbidden: Only project leader can update project');
            }
            req.body.archived = req.body.archived === 'on';

            const updatedProject = await Project.findByIdAndUpdate(req.params.id, req.body, { new: true });
            if (!updatedProject) {
                return res.status(404).send({ message: 'Project not found' });
            }

            res.format({
                html: function () {
                    res.redirect("/projects/" + updatedProject._id);
                },
                json: function () {
                    res.json(updatedProject);
                }
            });
        } catch (err) {
            res.status(400).send("Error updating project: " + err);
        }
    })
    // DELETE a specific project by ID
    .delete(ensureAuthenticated, async function (req, res) {
        try {
            // Check if user is the project leader
            if (req.project.leader._id.toString() !== req.user._id.toString()) {
                return res.status(403).send('Forbidden: Only project leader can delete project');
            }
            
            const deletedProject = await Project.findByIdAndDelete(req.project._id);
            if (!deletedProject) return res.status(404).send({ message: 'Project not found' });
            
            console.log('DELETE removing ID:', deletedProject._id);
            res.format({
                html: function () {
                    res.redirect("/projects");
                },
                json: function () {
                    res.json({ message: 'deleted', item: deletedProject });
                }
            });
        } catch (err) {
            res.status(500).send("Error deleting project: " + err);
        }
    });

/* GET Edit Project page. */
router.get('/:id/edit', ensureAuthenticated, async function (req, res, next) {
    try {
        // Check if user is the project leader
        if (req.project.leader._id.toString() !== req.user._id.toString()) {
            return res.status(403).send('Forbidden: Only project leader can edit');
        }

        res.format({
            html: function () {
                res.render('projects/edit', { title: 'Edit Project', project: req.project });
            },
            json: function () {
                res.json(req.project);
            }
        });
    } catch (err) {
        next(err);
    }
});

router.post('/:id/team', ensureAuthenticated, async function(req, res, next) {
    try {
        // Check if user is the project leader
        if (req.project.leader._id.toString() !== req.user._id.toString()) {
            return res.status(403).send('Forbidden: Only project leader can add team members');
        }
        
        const { userId, role } = req.body;
    
        // Provjeri je li korisnik već u timu
        const isAlreadyMember = req.project.teamMembers.some(member => 
            member.user && member.user._id.toString() === userId
        );
    
        if (!isAlreadyMember) {
            req.project.teamMembers.push({ user: userId, role });
            await req.project.save();
        }
    
        res.redirect(`/projects/${req.project._id}`);
    } catch (err) {
        next(err);
    }
});

router.delete('/:id/team/:memberId', ensureAuthenticated, async function(req, res, next) {
    try {
        // Check if user is the project leader
        if (req.project.leader._id.toString() !== req.user._id.toString()) {
            return res.status(403).send('Forbidden: Only project leader can remove team members');
        }
        
        req.project.teamMembers.pull({ _id: req.params.memberId });
        await req.project.save();
        res.redirect(`/projects/${req.project._id}`);
    } catch (err) {
        next(err);
    }
});

router.put('/:id/tasks', ensureAuthenticated, async (req, res, next) => {
    try {
        const project = await Project.findOne({
            _id: req.params.id,
            'teamMembers.user': req.user._id
        });
        
        if (!project) return res.status(403).send('Forbidden');
        
        project.tasks = req.body.tasks.split(',').map(t => t.trim());
        await project.save();
        
        res.redirect('/projects/member');
    } catch (err) {
        next(err);
    }
});

module.exports = router;
