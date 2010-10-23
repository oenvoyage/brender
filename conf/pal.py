import Blender
from Blender import *
from Blender.Scene import Render
 
scn = Scene.GetCurrent()
context = scn.getRenderingContext()
context.imageSizeX(720)
context.imageSizeY(576)
context.setOversamplingLevel(8)
context.enableOversampling(1)
context.setRenderWinSize(100)
context.enableRGBAColor()
