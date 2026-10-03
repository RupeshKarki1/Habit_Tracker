<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div
    class="modal"
    id="habit-modal"
    aria-hidden="true"
    >

    <!-- Dark background -->
    <div
        class="modal-backdrop"
        data-close-modal
    ></div>


    <!-- Modal box -->
    <div
        class="modal-dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="habit-modal-title"
    >

        <div class="modal-header">

            <div>
                <h2 id="habit-modal-title">
                    Add Habit
                </h2>

                <p>
                    Create a habit you want to track.
                </p>
            </div>


            <button
                type="button"
                class="modal-close"
                aria-label="Close modal"
                data-close-modal
            >
                &times;
            </button>

        </div>


        <form
            class="habit-form"
            id="habit-form"
            action="create-habit.php"
            method="POST"
        >

            <!-- Habit name -->
            <div class="form-group">

                <label for="habit-name">
                    Habit Name
                </label>

                <input
                    type="text"
                    id="habit-name"
                    name="habit_name"
                    placeholder="e.g. Morning Walk"
                    required
                >

            </div>

            <!-- Description -->
            <div class="form-group">

                <label for="habit-description">
                    Description (Optional)
                </label>

                <textarea
                    id="habit-description"
                    name="description"
                    placeholder="Describe your habit..."
                    rows="3"
                ></textarea>

            </div>


            <!-- Category -->
            <div class="form-group">

                <label for="habit-category">
                    Category
                </label>

                <select
                    id="habit-category"
                    name="category"
                    required
                >

                    <option value="">
                        Select category
                    </option>

                    <option value="health">
                        Health
                    </option>

                    <option value="fitness">
                        Fitness
                    </option>

                    <option value="learning">
                        Learning
                    </option>

                    <option value="productivity">
                        Productivity
                    </option>

                    <option value="other">
                        Other
                    </option>

                </select>

            </div>


            <!-- Frequency -->
            <div class="form-group">

                <label for="habit-frequency">
                    Frequency
                </label>

                <select
                    id="habit-frequency"
                    name="frequency"
                    required
                >

                    <option value="">
                        Select frequency
                    </option>

                    <option value="daily">
                        Daily
                    </option>

                    <option value="weekly">
                        Weekly
                    </option>

                </select>

            </div>


            <!-- Modal buttons -->
            <div class="modal-actions">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-close-modal
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Create Habit
                </button>

            </div>

        </form>

    </div>

    </div>
</body>
</html>


