import bpy

# --- we set the render context to the variable r
r=bpy.context.scene.render

# --- here we set the render resolution and pixel aspect ratio to Full HD
r.resolution_x=1024
r.resolution_y=576
r.pixel_aspect_x=1
r.pixel_aspect_y=1
r.resolution_percentage=50

# --- we might want to make sure AA is OFF, renders faster
r.use_antialiasing=False
r.antialiasing_samples='8'

# --- for test renders its cool to have stamps
r.use_stamp=True
r.use_stamp_note=True
r.stamp_note_text='Brender Preview conf'

# --- we can enable scene simplifications and set max subdivision to 0 
r.use_simplify=True
r.simplify_subdivision=0
