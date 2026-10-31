"use client";

import { useConversations, useSendMessage } from "@/lib/api/queries";
import { useAuth } from "@/components/auth/auth-context";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { MessageCircle, Send, Loader2 } from "lucide-react";
import { useState } from "react";
import toast from "react-hot-toast";

export default function MessagesPage() {
  const { user } = useAuth();
  const { data: conversationsData } = useConversations();
  const convList = conversationsData?.conversations || [];
  const sendMsg = useSendMessage();
  const [selectedConv, setSelectedConv] = useState<string | null>(null);
  const [message, setMessage] = useState("");

  const handleSend = async () => {
    if (!selectedConv || !message.trim()) return;
    try {
      await sendMsg.mutateAsync(
        { conversationId: selectedConv, content: message },
        { onSuccess: () => setMessage("") }
      );
    } catch (err: any) {
      toast.error(err.message);
    }
  };

  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold text-gray-900 mb-8">Messages</h1>
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div className="lg:col-span-1 bg-white rounded-2xl border">
          {convList.length === 0 ? (
            <div className="p-8 text-center text-gray-500">
              <MessageCircle className="w-12 h-12 mx-auto mb-3 text-gray-300" />
              <p>Aucun message</p>
              <p className="text-sm">Les conversations apparaîtront ici</p>
            </div>
          ) : (
            convList.map((conv: any) => (
              <button
                key={conv.id}
                onClick={() => setSelectedConv(conv.id)}
                className={`w-full text-left p-4 hover:bg-green-50 transition-colors ${
                  selectedConv === conv.id ? "bg-green-50 border-l-4 border-green-600" : ""
                }`}
              >
                <p className="font-medium text-sm">{conv.listing?.title || "Discussion"}</p>
                <p className="text-xs text-gray-500 mt-1">{conv.messages?.[0]?.content || "Cliquez pour lire"}</p>
              </button>
            ))
          )}
        </div>

        <div className="lg:col-span-2 bg-white rounded-2xl border" style={{ minHeight: "500px" }}>
          <div className="p-4 border-b">
            <h3 className="font-semibold">Conversation</h3>
          </div>
          <div className="p-4 space-y-3" style={{ minHeight: "400px" }}>
            {!selectedConv ? (
              <div className="flex items-center justify-center h-full text-gray-400">
                <p>Sélectionnez une conversation</p>
              </div>
            ) : (
              <div className="space-y-3">
                <div className="flex justify-start">
                  <div className="bg-gray-100 rounded-xl rounded-tl-none p-3 max-w-xs">
                    <p className="text-sm">Sélectionnez une conversation pour voir les messages</p>
                  </div>
                </div>
              </div>
            )}
          </div>
          <div className="p-4 border-t flex gap-2">
            <Input
              placeholder="Écrivez un message..."
              value={message}
              onChange={(e) => setMessage(e.target.value)}
              onKeyDown={(e) => e.key === "Enter" && handleSend()}
            />
            <Button onClick={handleSend} disabled={!message.trim()}>
              <Send className="w-4 h-4" />
            </Button>
          </div>
        </div>
      </div>
    </div>
  );
}
