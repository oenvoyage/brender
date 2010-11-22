import bpy

print("---------------")
rend=bpy.context.scene.render
rend.resolution_x=2048
rend.resolution_y=1080
rend.resolution_percentage=25
rend.render_antialiasing=0
print("---------------")

bpy.context.scene.world.lighting.use_indirect_lighting=False
bpy.context.scene.world.lighting.use_ambient_occlusion=False

