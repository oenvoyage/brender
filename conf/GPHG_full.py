import bpy

r=bpy.context.scene.render

r.resolution_x=1920
r.resolution_y=1080
r.resolution_percentage=100
r.pixel_aspect_x=1
r.pixel_aspect_y=1

r.use_antialiasing=True
r.antialiasing_samples='8'

r.use_simplify=False
r.color_mode='RGBA'
#r.simplify_subdivision=0

for ob in bpy.data.objects:
	if ob.dupli_group==bpy.data.groups["bubbletime_lo"]:
		ob.dupli_group=bpy.data.groups["bubbletime_hi"]
