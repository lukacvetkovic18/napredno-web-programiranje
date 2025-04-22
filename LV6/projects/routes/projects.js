var express = require('express'),
    router = express.Router(),
    mongoose = require('mongoose'), // MongoDB connection
    bodyParser = require('body-parser'), // Parses information from POST
    methodOverride = require('method-override'); // Used to manipulate POST

// Require the Project model
const Project = require('../models/project');

// Middleware for parsing and overriding methods
router.use(bodyParser.urlencoded({ extended: true }));
router.use(methodOverride(function (req, res) {
    if (req.body && typeof req.body === 'object' && '_method' in req.body) {
        var method = req.body._method;
        delete req.body._method;
        return method;
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
    .post(async function (req, res) {
        try {
            const project = new Project(req.body);
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
router.get('/new', function (req, res) {
    res.render('projects/new', { title: 'Add New Project' });
});

// Middleware to validate :id
router.param('id', async function (req, res, next, id) {
    try {
        const project = await Project.findById(id);
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

router.route('/:id')
    // GET a specific project by ID
    .get(async function (req, res, next) {
        try {
            const project = req.project;
            res.format({
                html: function () {
                    res.render('projects/show', { project: project });
                },
                json: function () {
                    res.json(project);
                }
            });
        } catch (err) {
            next(err);
        }
    })
    // PUT to update a specific project by ID
    .put(async function (req, res, next) {
      try {
          console.log("PUT request received for ID:", req.params.id);
          console.log("Request body:", req.body);

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
    .delete(async function (req, res) {
        try {
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
router.get('/:id/edit', async function (req, res, next) {
    try {
        const project = req.project;
        if (!project) return next(new Error('Not Found'));

        console.log("Editing project:", project);

        res.format({
            html: function () {
                res.render('projects/edit', { title: 'Edit Project', project: project });
            },
            json: function () {
                res.json(project);
            }
        });
    } catch (err) {
        next(err);
    }
});

router.post('/:id/team', async function (req, res, next) {
  try {
      const project = await Project.findById(req.params.id);
      if (!project) {
          return res.status(404).send({ message: 'Project not found' });
      }

      // Dodavanje novog člana tima
      const newMember = req.body; // Pretpostavljamo da sadrži name i role
      project.teamMembers.push(newMember);

      // Spremanje projekta s novim članom
      await project.save();

      res.format({
          html: function () {
              res.redirect(`/projects/${project._id}`);
          },
          json: function () {
              res.json(project);
          }
      });
  } catch (err) {
      next(err);
  }
});


module.exports = router;
