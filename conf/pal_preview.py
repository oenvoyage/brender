import bpy

r=bpy.context.scene.render

r.resolution_x=1024
r.resolution_y=576
r.pixel_aspect_x=1
r.pixel_aspect_y=1
r.resolution_percentage=50

r.use_antialiasing=False
#r.antialiasing_samples='8'

r.use_simplify=True
r.simplify_subdivision=0

