import os
import datetime
import time

# --- 1. Define the Target Date and Time Range ---

# Target Date: 29/11/2025
TARGET_DATE = datetime.date(2025, 11, 29)

# Start Time (4:30 PM)
START_HOUR = 16  # 4 PM in 24-hour format
START_MINUTE = 30
# End Time (5:15 PM)
END_HOUR = 17    # 5 PM in 24-hour format
END_MINUTE = 15

# Create the start and end datetime objects
START_DT = datetime.datetime(TARGET_DATE.year, TARGET_DATE.month, TARGET_DATE.day, START_HOUR, START_MINUTE, 0)
END_DT = datetime.datetime(TARGET_DATE.year, TARGET_DATE.month, TARGET_DATE.day, END_HOUR, END_MINUTE, 0)

# Convert datetimes to Unix timestamps for comparison
START_TS = START_DT.timestamp()
END_TS = END_DT.timestamp()

# --- 2. Define the Search Directory ---
# The script will search the directory where it is executed and all its subfolders.
SEARCH_DIR = '.' 

print(f"🕵️ Searching files recursively in: {os.path.abspath(SEARCH_DIR)}")
print(f"⏳ Time Range: {START_DT.strftime('%Y-%m-%d %H:%M:%S')} to {END_DT.strftime('%Y-%m-%d %H:%M:%S')}\n")
print("-" * 60)
print("✅ Files Found:")
print("-" * 60)

# --- 3. Iterate Recursively and Check Files ---

found_count = 0

try:
    # os.walk() generates the file names in a directory tree
    for root, dirs, files in os.walk(SEARCH_DIR):
        # 'files' is a list of all files in the current directory ('root')
        for file_name in files:
            full_path = os.path.join(root, file_name)

            # Skip searching symbolic links to prevent infinite loops (optional but good practice)
            if os.path.islink(full_path):
                continue
            
            try:
                # os.path.getmtime() returns the modification time as a Unix timestamp
                modification_timestamp = os.path.getmtime(full_path)

                # Check if the modification time is within the defined range
                if START_TS <= modification_timestamp <= END_TS:
                    # Convert the timestamp back to a readable datetime object for printing
                    mod_dt = datetime.datetime.fromtimestamp(modification_timestamp)
                    
                    # Print the full path relative to the starting directory and its modification time
                    print(f"- {full_path} (Modified: {mod_dt.strftime('%H:%M:%S')})")
                    found_count += 1

            except OSError as e:
                # Handle cases where file metadata can't be accessed (e.g., permission denied)
                print(f"  [Error accessing metadata for {full_path}: {e}]")

    print("-" * 60)
    if found_count == 0:
        print("No files were modified in the specified time range within this directory or its subfolders.")
    else:
        print(f"Total files found: {found_count}")

except Exception as e:
    print(f"An unexpected error occurred: {e}")