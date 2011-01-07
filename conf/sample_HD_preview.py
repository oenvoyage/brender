import bpy

# --- we set the render context to the variable r
r=bpy.context.scene.render

# --- here we can set the render resolution and pixel aspect ratio to half HD
r.resolution_x=960
r.resolution_y=540
r.pixel_aspect_x=1
r.pixel_aspect_y=1
r.resolution_percentage=100

# --- we might want to disable antialiasing for faster preview rendering
r.use_antialiasing=False
#r.antialiasing_samples='8'

# --- to make sure every preview render gets a stamp we force it and add a little note
r.use_stamp=True
r.use_stamp_note=True
r.stamp_note='Brender Preview Sample conf'

# --- we can enable scene simplifications and set max subdivision to 0 
r.use_simplify=True
r.simplify_subdivision=0

