from transformers import AutoModelForCausalLM, AutoTokenizer
import torch

# Load GPT-Neo model
model_name = "EleutherAI/gpt-neo-1.3B"  # Options: 125M, 1.3B, 2.7B
tokenizer = AutoTokenizer.from_pretrained(model_name)
model = AutoModelForCausalLM.from_pretrained(model_name)

# Set pad_token to eos_token to avoid issues
tokenizer.pad_token = tokenizer.eos_token

# Chat loop
print("Chatbot: Hi! Ask me anything.")
chat_history = ""

while True:
    user_input = input("You: ")
    if user_input.lower() in ["exit", "quit"]:
        print("Chatbot: Goodbye!")
        break

    # Tokenize input and add it to chat history
    inputs = tokenizer(chat_history + user_input, return_tensors="pt", padding=True, truncation=True, max_length=512)

    # Ensure input tensor is on the correct device (GPU or CPU)
    device = torch.device("cuda" if torch.cuda.is_available() else "cpu")
    model.to(device)
    inputs = {key: val.to(device) for key, val in inputs.items()}

    # Generate response
    outputs = model.generate(
        inputs["input_ids"],
        attention_mask=inputs["attention_mask"],  # Pass attention mask explicitly
        max_length=150,
        num_return_sequences=1,
        pad_token_id=tokenizer.eos_token_id
    )

    # Decode the response
    response = tokenizer.decode(outputs[0], skip_special_tokens=True)

    # Update chat history
    chat_history += f"{user_input} {response} "

    print(f"Chatbot: {response}")
