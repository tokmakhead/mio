import os
import zipfile
import subprocess
import shutil

PROJECT_DIR = os.getcwd()
OUTPUT_NAME = "mionex-v1.0-dist.zip"
TEMP_EXPORT_DIR = "mionex_export_temp"

def run_command(command):
    print(f"Executing: {command}")
    result = subprocess.run(command, shell=True, capture_output=True, text=True)
    if result.returncode != 0:
        print(f"Error: {result.stderr}")
        return False
    return True

def package():
    # 1. Ensure assets are built
    print("Step 1: Building assets...")
    if not run_command("npm run build"):
        return

    # 2. Use git archive to get a clean set of files (respecting .gitattributes)
    print("Step 2: Exporting clean source using git archive...")
    if os.path.exists(TEMP_EXPORT_DIR):
        shutil.rmtree(TEMP_EXPORT_DIR)
    os.makedirs(TEMP_EXPORT_DIR)
    
    # Export to temp directory
    if not run_command(f"git archive HEAD | tar -x -C {TEMP_EXPORT_DIR}"):
        print("Fallback: archive to zip then extract...")
        if run_command(f"git archive --format=zip -o temp_archive.zip HEAD"):
            with zipfile.ZipFile("temp_archive.zip", 'r') as zip_ref:
                zip_ref.extractall(TEMP_EXPORT_DIR)
            os.remove("temp_archive.zip")
        else:
            print("Failed to export source.")
            return

    # 3. Add necessary files that might be ignored but needed
    # Usually 'public/build' is gitignored but needed for production
    print("Step 3: Copying production assets...")
    shutil.copytree(
        os.path.join(PROJECT_DIR, "public", "build"),
        os.path.join(TEMP_EXPORT_DIR, "public", "build"),
        dirs_exist_ok=True
    )

    # 4. Final Cleanup of temp dir
    print("Step 4: Final cleanup...")
    # Add an empty .env file if it doesn't exist (as example)
    env_example = os.path.join(TEMP_EXPORT_DIR, ".env.example")
    if os.path.exists(env_example):
        shutil.copy(env_example, os.path.join(TEMP_EXPORT_DIR, ".env"))

    # 5. Create final ZIP
    print(f"Step 5: Creating {OUTPUT_NAME}...")
    with zipfile.ZipFile(OUTPUT_NAME, 'w', zipfile.ZIP_DEFLATED) as zipf:
        for root, dirs, files in os.walk(TEMP_EXPORT_DIR):
            for file in files:
                file_path = os.path.join(root, file)
                arcname = os.path.relpath(file_path, TEMP_EXPORT_DIR)
                zipf.write(file_path, arcname)

    # Clean up temp
    shutil.rmtree(TEMP_EXPORT_DIR)
    print(f"Success! {OUTPUT_NAME} created in {PROJECT_DIR}")

if __name__ == "__main__":
    package()
