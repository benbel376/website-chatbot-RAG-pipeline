import subprocess
import sys
import os

def install_requirements(requirements_file='requirements.txt'):
    if not os.path.exists(requirements_file):
        print(f"Error: {requirements_file} not found.")
        sys.exit(1)

    try:
        # Run pip install using subprocess
        result = subprocess.run([sys.executable, '-m', 'pip', 'install', '-r', requirements_file], check=True)
        print("Requirements installed successfully.")
    except subprocess.CalledProcessError as e:
        print(f"An error occurred while installing requirements: {e}")
        sys.exit(1)

if __name__ == '__main__':
    install_requirements()
