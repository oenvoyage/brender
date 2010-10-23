import Blender
from Blender import *
from Blender.Scene import Render
 
scn = Scene.GetCurrent()
context = scn.getRenderingContext()
context.imageSizeX(1920)
context.imageSizeY(1080)
context.setOversamplingLevel(8)
context.enableOversampling(1)
context.setRenderWinSize(100)
context.enableRGBAColor()
