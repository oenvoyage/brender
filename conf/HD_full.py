import bpy

# --- we set the render context to the variable r
r=bpy.context.scene.render

# --- here we set the render resolution and pixel aspect ratio to Full HD
r.resolution_x=1920
r.resolution_y=1080
r.pixel_aspect_x=1
r.pixel_aspect_y=1
r.resolution_percentage=100

# --- we might want to make sure AA is ON, and force to use 8 samples
r.use_antialiasing=True
r.antialiasing_samples='8'

# --- final production renders should not have stamps, make sure they are not used
r.use_stamp=False

# the same goes for scene simplifications, 
r.use_simplify=False

