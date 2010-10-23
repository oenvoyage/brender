import bpy

print("---------------")
rend=bpy.context.scene.render
rend.resolution_x=2048
rend.resolution_y=1080
rend.resolution_percentage=50
rend.render_antialiasing=5
print("---------------")
