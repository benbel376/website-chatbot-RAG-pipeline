<div class="detail-unit details-unit" id="image-segmentation-details" hidden>
    <div class="blog-page active" id="page-1">
        
        <h2 class="project-detail-title">Retina Blood Vessel Segmentation</h2>
        
        <h3 class="project-detail-subtitle">Subtitle: Deep Learning Model for Segmenting Blood Vessels in Retina Images</h3>
        
        <div class="blog-details-banner">
            <img src="./assets/images/blood.png" alt="Blog Banner">
        </div>
      
        <h3 class="blog-post-mid-title">Introduction</h3>
        <p class="plog-post-body">
            Blood vessel segmentation in retinal images is crucial for the diagnosis and monitoring of various eye diseases, such as diabetic retinopathy and glaucoma. By accurately segmenting blood vessels, doctors can identify changes in the retinal structure that are indicative of health issues. In this project, we developed a U-Net model for automatic segmentation of blood vessels in retina images. We will cover data preparation, model architecture, training, and deployment using FastAPI and Docker. 
            <br><br>
            Through this blog, you will gain insights into how deep learning can be applied to medical imaging and learn how to set up a real-world project from training a model to deploying it with a REST API.
        </p>
      
        <h3 class="blog-post-mid-title">1. Data Preparation</h3>
        <p class="plog-post-body">
            The first step is data preparation, where we organize the images and masks. In this project, we use a custom `DriveDataset` class to load the retina images and their corresponding blood vessel masks. Each image is normalized and resized to 512x512 pixels for input into the U-Net model.
        </p>
      
        <div class="code-display-wrapper" id="custom-code-display">
            <div class="code-display-header">
                <span class="code-display-filename">python</span>
                <span class="code-display-actions">
                    <button class="code-display-copy">Copy</button>
                </span>
            </div>
            <div class="code-display-body">
                <pre><code id="code-content">
from torch.utils.data import Dataset
import cv2
import numpy as np

class DriveDataset(Dataset):
    def __init__(self, images_path, masks_path):
        self.images_path = images_path
        self.masks_path = masks_path
        self.n_samples = len(images_path)

    def __getitem__(self, index):
        image = cv2.imread(self.images_path[index], cv2.IMREAD_COLOR)
        image = image / 255.0
        image = np.transpose(image, (2, 0, 1)).astype(np.float32)
        
        mask = cv2.imread(self.masks_path[index], cv2.IMREAD_GRAYSCALE) / 255.0
        mask = np.expand_dims(mask, axis=0).astype(np.float32)

        return image, mask

    def __len__(self):
        return self.n_samples
                </code></pre>
            </div>
        </div>
      
        <h3 class="blog-post-mid-title">2. Model Architecture</h3>
        <p class="plog-post-body">
            For this project, we implemented a U-Net architecture, which is widely used in biomedical image segmentation due to its encoder-decoder structure. The encoder captures context and extracts features from the input image, while the decoder uses those features to produce precise segmentation maps. This structure is especially suited for segmenting complex structures like blood vessels.
        </p>
      
        <div class="code-display-wrapper" id="custom-code-display">
            <div class="code-display-header">
                <span class="code-display-filename">python</span>
                <span class="code-display-actions">
                    <button class="code-display-copy">Copy</button>
                </span>
            </div>
            <div class="code-display-body">
                <pre><code id="code-content">
import torch
import torch.nn as nn

class UNet(nn.Module):
    def __init__(self):
        super(UNet, self).__init__()
        # Encoder
        self.enc1 = self.conv_block(3, 64)
        self.enc2 = self.conv_block(64, 128)
        # Bottleneck
        self.bottleneck = self.conv_block(128, 256)
        # Decoder
        self.dec1 = self.conv_block(256, 128)
        self.dec2 = self.conv_block(128, 64)
        self.final_layer = nn.Conv2d(64, 1, kernel_size=1)

    def conv_block(self, in_channels, out_channels):
        return nn.Sequential(
            nn.Conv2d(in_channels, out_channels, kernel_size=3, padding=1),
            nn.ReLU(inplace=True),
            nn.Conv2d(out_channels, out_channels, kernel_size=3, padding=1),
            nn.ReLU(inplace=True),
        )

    def forward(self, x):
        enc1 = self.enc1(x)
        enc2 = self.enc2(enc1)
        bottleneck = self.bottleneck(enc2)
        dec1 = self.dec1(bottleneck + enc2)
        dec2 = self.dec2(dec1 + enc1)
        return torch.sigmoid(self.final_layer(dec2))
                </code></pre>
            </div>
        </div>
      
        <h3 class="blog-post-mid-title">3. Training the Model</h3>
        <p class="plog-post-body">
            The model is trained using the Dice coefficient and Binary Cross-Entropy (BCE) as the loss function, which helps improve segmentation accuracy by penalizing differences between predicted and actual masks. The model is optimized using Adam, with a learning rate scheduler that adjusts the learning rate based on validation loss.
        </p>
      
        <div class="code-display-wrapper" id="custom-code-display">
            <div class="code-display-header">
                <span class="code-display-filename">python</span>
                <span class="code-display-actions">
                    <button class="code-display-copy">Copy</button>
                </span>
            </div>
            <div class="code-display-body">
                <pre><code id="code-content">
from torch.optim import Adam
from losses import DiceBCELoss

device = torch.device('cuda' if torch.cuda.is_available() else 'cpu')
model = UNet().to(device)
optimizer = Adam(model.parameters(), lr=1e-4)
loss_fn = DiceBCELoss()

for epoch in range(num_epochs):
    model.train()
    for images, masks in train_loader:
        images, masks = images.to(device), masks.to(device)
        
        optimizer.zero_grad()
        output = model(images)
        loss = loss_fn(output, masks)
        loss.backward()
        optimizer.step()
                </code></pre>
            </div>
        </div>
      
        <h3 class="blog-post-mid-title">4. Deployment with FastAPI</h3>
        <p class="plog-post-body">
            After training, the model is deployed using FastAPI, which allows users to upload retina images and receive segmented masks. The model is containerized with Docker and uses NGINX as a reverse proxy. This setup makes the application scalable and easy to deploy across various environments.
        </p>
      
        <div class="code-display-wrapper" id="custom-code-display">
            <div class="code-display-header">
                <span class="code-display-filename">python</span>
                <span class="code-display-actions">
                    <button class="code-display-copy">Copy</button>
                </span>
            </div>
            <div class="code-display-body">
                <pre><code id="code-content">
from fastapi import FastAPI, UploadFile, File
import torch
import cv2
import numpy as np

app = FastAPI()
model = UNet().to(device)
model.load_state_dict(torch.load("checkpoint.pth", map_location=device))

@app.post("/predict/")
async def predict(file: UploadFile = File(...)):
    image_bytes = await file.read()
    image = cv2.imdecode(np.frombuffer(image_bytes, np.uint8), cv2.IMREAD_COLOR)
    image = cv2.resize(image, (512, 512)) / 255.0
    image = torch.tensor(image).permute(2, 0, 1).unsqueeze(0).to(device)

    with torch.no_grad():
        output = model(image)
        mask = (output.squeeze().cpu().numpy() > 0.5).astype(np.uint8)

    return {"mask": mask.tolist()}
                </code></pre>
            </div>
        </div>
      
        <h3 class="blog-post-mid-title">Conclusion</h3>
        <p class="plog-post-body">
            This project demonstrates how deep learning can be used to perform blood vessel segmentation in retinal images, contributing to advancements in medical imaging. By following a structured pipeline from data preparation to deployment, this project highlights the practical steps involved in building, training, and deploying a U-Net model for medical segmentation tasks. This application can assist healthcare providers in diagnosing retinal diseases efficiently and accurately.
        </p>
      
    </div>

    <!-- Blog Pagination Section -->
    <div class="blog-pagination-container">
        <button class="blog-prev-page" data-blog-prev-page disabled>Previous</button>
        <span class="blog-pagination-info">Page 1 of 3</span>
        <button class="blog-next-page" data-blog-next-page>Next</button>
    </div>
</div>
